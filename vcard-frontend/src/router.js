import { createWebHistory, createRouter } from "vue-router"
import Dashboard from "./components/Dashboard.vue"
import Login from "./components/auth/Login.vue"
import Register from "./components/auth/Register.vue"
import ConfirmationCode from "./components/auth/ConfirmationCode.vue"
import ConfirmationPhoneNumber from "./components/auth/ConfirmationPhoneNumber.vue"
import CreateTransaction from "./components/transactions/CreateTransaction.vue"
import CreateCategory from "./components/categories/CreateCategory.vue"
import ChangeConfirmationCode from "./components/profile/ChangeConfirmationCode.vue"
import ChangePassword from "./components/profile/ChangePassword.vue"
import DeleteProfile from "./components/profile/DeleteProfile.vue"
// lazy-loaded
const Profile = () => import("./components/profile/Profile.vue")
const EditProfile = () => import("./components/profile/EditProfile.vue")
const BoardAdmin = () => import("./components/admin/BoardAdmin.vue")
const BoardUsers = () => import("./components/users/BoardUsers.vue")
const BoardProfile = () => import("./components/profile/BoardProfile.vue")
const BoardTransactions = () => import("./components/transactions/BoardTransactions.vue")
const BoardCategories = () => import("./components/categories/BoardCategories.vue")
const CategoriesTable = () => import("./components/categories/CategoriesTable.vue")

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
    name: "dashboard",
    component: Dashboard,
  },
  {
    path: '/confirmationCode',
    component: ConfirmationCode,
    props: true,
    name: "confirmationCode",
    // lazy-loaded
  },
  {
    path: '/changePassword',
    component: ChangePassword,
    props: true,
    name: "changePassword",
    // lazy-loaded
  },
  {
    path: '/changeConfirmationCode',
    component: ChangeConfirmationCode,
    props: true,
    name: "changeConfirmationCode",
    // lazy-loaded
  },
  {
    path: '/confirmationPhoneNumber/:phoneNumber',
    component: ConfirmationPhoneNumber,
    props: true,
    name: "confirmationPhoneNumber",
    // lazy-loaded
  },
  {
    path: '/profile',
    component: BoardProfile,
    props: true,
    children: [
      {
        path: '',
        component: Profile,
        name: "showProfile",
        // lazy-loaded
      },
      {
        path: 'edit',
        component: EditProfile,
        props: true,
        name: "editProfile",
        // lazy-loaded
      },
      {
        path: '/changePassword',
        component: ChangePassword,
        props: true,
        name: "changePassword",
        // lazy-loaded
      },
      {
        path: '/changeConfirmationCode',
        component: ChangeConfirmationCode,
        props: true,
        name: "changeConfirmationCode",
        // lazy-loaded
      },
      {
        path: '/deleteProfile',
        component: DeleteProfile,
        props: true,
        name: "deleteProfile",
        // lazy-loaded
      },
    ]
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
    path: "/transactions/create",
    name: "createTransaction",
    component: CreateTransaction,
  },
  {
    path: "/categories",
    name: "categories",
    // lazy-loaded
    component: BoardCategories,
    children: [
      {
        path: "",
        name: "categoriesTable",
        // lazy-loaded
        component: CategoriesTable,
      },
      {
        path: "create",
        name: "createCategory",
        // lazy-loaded
        component: CreateCategory,
      },
    ]
  },
  {
    path: "/admin",
    name: "admin",
    // lazy-loaded
    component: BoardAdmin,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router