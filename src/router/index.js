import { createRouter, createWebHistory } from "vue-router";
import Teachers from "@/components/Teachers.vue";
import Faculties from "@/components/Faculties.vue";
import Disciplines from "@/components/Disciplines.vue";
import Departments from "@/components/Departments.vue";
import Students from "@/components/Students.vue";
import Groups from "@/components/Groups.vue";
import Schedule from "@/components/Schedule.vue";
import FAQ from "@/components/FAQ.vue";

const routes = [
  { path: "/groups", component: Groups },
  { path: "/teachers", component: Teachers },
  { path: "/faculties", component: Faculties },
  { path: "/students", component: Students },
  { path: "/disciplines", component: Disciplines },
  { path: "/departments", component: Departments },
  { path: "/schedule", component: Schedule },
  { path: "/faq", component: FAQ },
  //   {
  //     path: "/",
  //     name: "teachers",
  //     component: Teachers,
  //   },
  //   {
  //     path: "/",
  //     name: "registeredUsers",
  //     component: RegisterUsers,
  //   },
];

// const routes = [
//   {
//     path: "/",
//     name: "home",
//     component: HomeView,
//   },
//   {
//     path: "/about",
//     name: "about",
//     // route level code-splitting
//     // this generates a separate chunk (about.[hash].js) for this route
//     // which is lazy-loaded when the route is visited.
//     component: () =>
//       import(/* webpackChunkName: "about" */ "../views/RegisterUsersView.vue"),
//   },
// ];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: routes,
});

export default router;
