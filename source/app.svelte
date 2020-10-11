<script>
	import Router, { location, link, push } from 'svelte-spa-router';
	import { DESKTOP_WIDTH, TOGGLE_DELAY, userData } from './js/stores';
	import { onMount } from 'svelte';
	import Index from './pages/index.svelte';
	import Login from './pages/login.svelte';
	import Patients from './pages/patients.svelte';
	import Patient from './pages/patient.svelte';
	import Profile from './pages/profile.svelte';

	let windowWidth;
	let openMenuFlag = false; // открыто ли мобильное меню
	let allowFlag = false;
	userData.subscribe((value) => {
		allowFlag = Boolean(value.login);
	});

	const pages = [
		{
			href: `/`,
			title: `Главная`
		},
		{
			Component: Patients,
			route: `/patients`,
			title: `Пациенты`
		},
		{
			Component: Patient,
			route: `/patients/:id`
		},
		{
			Component: Profile,
			route: `/profile`,
			title: `Настройки профиля`
		}
	];

	const routes = {};
	const menu = [];
	for (const page of pages) {
		if (!page.href && page.route) {
			page.href = page.route;
		}
		if (page.Component && page.route && page.route !== `*`) {
			routes[page.route] = page.Component;
		}
		if (page.href && page.title) {
			menu.push(page);
		}
	}
	routes[`*`] = Index;

	function toggleMenu() {
		openMenuFlag = !openMenuFlag;
	}

	function closeMenu() {
		openMenuFlag = false;
	}

	function resizeHandler() {
		if (windowWidth >= DESKTOP_WIDTH) {
			openMenuFlag = true;
		}
	}

	onMount(resizeHandler);
</script>

<svelte:window bind:innerWidth={windowWidth} on:resize={resizeHandler}/>

<header class="header header--{openMenuFlag ? `opened` : `closed`} page__header">
	{#if allowFlag}
		<button class="header__toggler header__toggler--{openMenuFlag ? `closer` : `opener`}" on:click={toggleMenu}>
			<span class="sr-only">{openMenuFlag ? `Закрыть` : `Открыть`} меню</span>
		</button>
		<ul class="header__menu">
			{#each menu as {href, title}}
				<li>
					{#if href === $location}
						<span class="header__link header__link--current">{title}</span>
					{:else if href !== `/` && $location.indexOf(href) !== -1}
						<a class="header__link header__link--parent" {href} use:link on:click={closeMenu}>{title}</a>
					{:else}
						<a class="header__link" {href} use:link on:click={closeMenu}>{title}</a>
					{/if}
				</li>
			{/each}
		</ul>
	{/if}
</header>

<main class="page__main">
	{#if allowFlag}
		<Router {routes}/>
	{:else}
		<Login/>
	{/if}
</main>

<style global lang="less">
  @import "less/style";
</style>
