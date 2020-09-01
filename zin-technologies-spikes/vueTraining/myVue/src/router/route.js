import HelloWorld from '../components/HelloWorld'
import Parent from '../components/Parent'
import registerUser from '../components/registerUserParent.vue'
import childRoute from '../components/child.vue'
import secondChildRoute from '../components/secondChild.vue'
import optionalVue from '../components/optional'
import notFound from '../components/notFound'
import parameterComponent from '../components/parameterComponent'



export default [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld,
      meta: { requiresAuth: false }
    },
    {
      path: '/parameter/:id?', // by adding question mark the parameter id will be optional means /paratmer will work, by removing question mark /parameter will not work but /parameter/{someId} will work
      name: 'passingParameter',
      component: parameterComponent,
      meta: { requiresAuth: false }
    },
    {
      path: '/addUsers',
      name: 'users',
      component: Parent,
      meta: { requiresAuth: false } 
    },
    {
      path: '/registerUsers',
      name: 'register',
      component: registerUser,
      meta: { requiresAuth: true },
      children: [
            {
                path: '/childRoute',
                name: 'childRouteExample',
                component: childRoute,
                meta: { requiresAuth: true }
            },
            {
                path: '/secondChildRoute',
                name: 'secondChildRouteExample',
                components: {
                    default: secondChildRoute,
                    optional: optionalVue
                },
                meta: { requiresAuth: true }
            }
        ]
    },
    {
      path: '*',
      name: 'notFound',
      components: {
        notFound: notFound
      }
    },
  ]
