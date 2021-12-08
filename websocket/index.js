const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  allowEIO3: true,
  cors: {
    origin: "http://localhost:8081", //"http://172.22.21.95",
    methods: ["GET", "POST"],
    credentials: true,
  },
});
httpServer.listen(8080, function () {
  console.log("listening on *:8080");
});
io.on("connection", function (socket) {

  console.log(`Browser ${socket.id} has connected`);

  socket.on("newTransaction", function (transaction) {
      let to = transaction.type == 'C' ? transaction.vcard : transaction.payment_reference;
      let from = transaction.type == 'D' ? transaction.vcard : transaction.payment_reference
      console.log(`${new Date()} - A new transaction from ${from} has created to - ${ to }`);
      socket.to(to+'').emit('newTransaction',`You recieved ${transaction.value} € from ${from}`);
      socket.to("administrator").emit('newTransaction',`New Transaction of ${transaction.value} €`, transaction.payment_type === "VCARD" ? 2 : 1);
  });

  socket.on("userDeleted", function (deletedData) {
    console.log(`${new Date()} - User from ${deletedData.to} has deleted to - ${ deletedData.from }`);
    socket.to(deletedData.to+'').emit('userDeleted',`${deletedData.to} you are deleted!`);
  });

  socket.on("vcardBlocked", function (blockedData) {
    console.log(`${new Date()} - User from ${blockedData.to} has deleted to - ${ blockedData.from }`);
    socket.to(blockedData.to+'').emit('userDeleted',`${blockedData.to} you are blocked!`);
  });

  socket.on("disconnect", function () {
    console.log(`Browser ${socket.id} has disconected. Left ${socket.rooms.size} rooms active.`);
  })

  socket.on("logged_in", function (user) {
    if (user.id) {
      console.log(`${new Date()} - User ${user.id} has connected`);
      socket.join(user.id+'');

      socket.emit(user.id, 'u are connected');
      if (user.user_type == "A") {
        socket.join("administrator");
        console.log(`${new Date()} - administrator ${socket.id} has connected`);
      }
      if (user.user_type == "V") {
        socket.join("vcard");
        console.log(`${new Date()} - vcard ${socket.id} has connected`);
      }
    }
  });

  socket.on("logged_out", function (user) {
    console.log('User '+user.id+' logged out')
    socket.leave(user.id+'');
    socket.leave("administrator");
    socket.leave("vcard");
  });
});