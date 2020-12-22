import barat from '../../components/barat/index.vue';
import walima from '../../components/walima/index.vue';
import menu from '../../components/menu/index.vue';
import notFound from '../../components/notFound/index.vue';


const routes = [
  {
    path: "/",
    component: menu,
  },
  {
    path: "/barat",
    component: barat,
  },
  {
    path: "/walima",
    component: walima,
  },
  {
    path: '*',
    component: notFound,
  }
];

export default routes;
