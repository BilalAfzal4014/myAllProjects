import login from '../components/login';
import dashboard from '../components/dashboard/dashboard';
import userDetails from '../components/dashboard/userDetails/userDetails';
import userListing from '../components/dashboard/userListing/userListing';
import chat from '../components/dashboard/chat/chat';

export default[
	{
		path: '/',
		component: login,
		name: 'loginPage',
		meta: { requiresAuth: false }

	},
	{
		path: '/dashboard',
		component: dashboard,
		name: 'dashboardPage',
		meta: { requiresAuth: true },
		children: [
			{
				path: '/dashboard/userDetails',
				component: userDetails,
				name: 'userDetails',
				meta: { requiresAuth: true },
			},
			{
				path: '/dashboard/userListing',
				component: userListing,
				name: 'userListing',
				meta: { requiresAuth: true },
			},
			{
				path: '/dashboard/chat',
				component: chat,
				name: 'chat',
				meta: { requiresAuth: true },
			},
		]

	}
	

]