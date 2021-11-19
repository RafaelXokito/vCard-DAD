import { createWebHistory, createRouter } from "vue-router";
import Home from "./components/Home.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import ConfirmationCode from "./components/ConfirmationCode.vue";
import ConfirmationPhoneNumber from "./components/ConfirmationPhoneNumber.vue";
import CreateTransaction from "./components/CreateTransaction.vue";

// lazy-loaded
const Profile = () => import("./components/Profile.vue")
const BoardAdmin = () => import("./components/BoardAdmin.vue")
const BoardUsers = () => import("./components/BoardUsers.vue")
const BoardTransactions = () => import("./components/BoardTransactions.vue")
const BoardCategories = () => import("./components/BoardCategories.vue")

const routes = [
  {
    path: "/login",
    name: "login",
    component: Login,
  },
  {
    path: "/register",
    name: "register",
    component: Register,
  },
  {
    path: "/",
    name: "home",
    component: Home,
  },
  {
    path: '/confirmationCode',
    component: ConfirmationCode,
    props: true,
    name: "confirmationCode",
    // lazy-loaded
  },
  {
    path: '/confirmationPhoneNumber',
    component: ConfirmationPhoneNumber,
    props: true,
    name: "confirmationPhoneNumber",
    // lazy-loaded
  },
  {
    path: '/profile',
    component: Profile,
    props: true,
    name: "profile",
    // lazy-loaded
  },
  {
    path: "/users",
    name: "users",
    // lazy-loaded
    component: BoardUsers,
  },
  {
    path: "/transactions",
    name: "transactions",
    // lazy-loaded
    component: BoardTransactions,
  },
  {
    path: "/createTransaction",
    name: "createTransaction",
    component: CreateTransaction,
  },
  {
    path: "/categories",
    name: "categories",
    // lazy-loaded
    component: BoardCategories,
  },
  {
    path: "/admin",
    name: "admin",
    // lazy-loaded
    component: BoardAdmin,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;