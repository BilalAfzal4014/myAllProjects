import routes from "./routes";
import VueRouter from "vue-router";
const router = new VueRouter({
    mode: "history",
    routes,
    scrollBehavior() {
        document.getElementById("app").scrollIntoView();
    }
});
export default router;