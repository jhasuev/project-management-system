import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './components/Home'
import Login from './components/Auth/Login'
import Register from './components/Auth/Register'
import Profile from './components/User/Profile'

import Dashboard from './components/Dashboard/Dashboard'
import Board from './components/Dashboard/Board'

Vue.use(VueRouter)

export default new VueRouter({
	routes : [
		{
			path : '/',
			// redirect : '/dashboard',
			component : Home,
		},
		{
			path : '/login',
			component : Login,
		},
		{
			path : '/register',
			component : Register,
		},
		{
			path : '/profile',
			component : Profile,
		},
		{
			path : '/dashboard',
			component : Dashboard,
		},
		{
			path : '/board/:id',
			props: true,
			component : Board,
		},
	],
	mode : 'history'
});