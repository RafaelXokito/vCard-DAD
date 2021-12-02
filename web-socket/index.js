const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  allowEIO3: true,
  cors: {
    origin: "http://localhost:8081",
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
    console.log(`A new transaction ${transaction} has created`);
    socket.broadcast.emit("newTransaction", transaction);
  });

  socket.on("logged_in", function (user) {
    socket.join(user.id);
    if (user.user_type == "A") {
      socket.join("administrator");
      console.log(`administrator ${socket.id} has connected`);
    }
    if (user.user_type == "V") {
      socket.join("vcard");
      console.log(`vcard ${socket.id} has connected`);
    }
  });
  socket.on("logged_out", function (user) {
    socket.leave(user.id);
    socket.leave("administrator");
  });
});
