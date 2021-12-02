import { createWebHistory, createRouter } from "vue-router"
import Dashboard from "./components/Dashboard.vue"
import Login from "./components/auth/Login.vue"
import Register from "./components/auth/Register.vue"
import ConfirmationCode from "./components/auth/ConfirmationCode.vue"
import ConfirmationPhoneNumber from "./components/auth/ConfirmationPhoneNumber.vue"
import CreateTransaction from "./components/transactions/CreateTransaction.vue"
import CreateCategory from "./components/categories/CreateCategory.vue"
import CreateEditDefaultCategory from "./components/default_categories/CreateEditDefaultCategories.vue"
//import CreateEditPaymentType from "./components/payment_types/CreateEditPaymentTypes.vue"
import ChangeConfirmationCode from "./components/profile/ChangeConfirmationCode.vue"
import ChangePassword from "./components/profile/ChangePassword.vue"
import DeleteProfile from "./components/profile/DeleteProfile.vue"
// lazy-loaded
const Profile = () => import("./components/profile/Profile.vue")
const EditProfile = () => import("./components/profile/EditProfile.vue")
const CreateAdmin = () => import("./components/admin/CreateAdmin.vue")
const BoardAdmin = () => import("./components/admin/BoardAdmin.vue")
const BoardUsers = () => import("./components/users/BoardUsers.vue")
const BoardProfile = () => import("./components/profile/BoardProfile.vue")
const BoardTransactions = () => import("./components/transactions/BoardTransactions.vue")
const BoardCategories = () => import("./components/categories/BoardCategories.vue")
const BoardDefaultCategories = () => import("./components/default_categories/BoardDefaultCategories.vue")
//const BoardPaymentType = () => import("./components/payment_types/BoardPaymentTypes.vue")
const DefaultCategoriesTable = () => import("./components/default_categories/DefaultCategoriesTable.vue")
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
    path: "/createAdmin",
    name: "createAdmin",
    component: CreateAdmin
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
        props: true,
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
    path: "/defaultCategories",
    name: "defaultCategories",
    // lazy-loaded
    component: BoardDefaultCategories,
    children: [
      {
        path: "",
        name: "defaultCategoriesTable",
        props: true,
        // lazy-loaded
        component: DefaultCategoriesTable,
      },
      {
        path: "createEdit",
        name: "createEditDefaultCategory",
        // lazy-loaded
        component: CreateEditDefaultCategory,
      },
    ]
  },
  /*{
    path: "/paymentType",
    name: "paymentType",
    // lazy-loaded
    component: BoardPaymentType,
    children: [
      {
        path: "createEdit",
        name: "createEditPaymentType",
        // lazy-loaded
        component: CreateEditPaymentType,
      },
    ]
  },*/
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