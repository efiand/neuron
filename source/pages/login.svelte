<script>
	import { push } from 'svelte-spa-router';
	import { TOGGLE_DELAY, userData } from '../js/stores';

	let form;
	let showPage = true;
	let formData = {};
	userData.subscribe((value) => {
		formData = {
			...value,
			page: `/login`,
			validableClass: ``,
			status: ``
		};
	});

	async function submitHandler() {
		formData.validableClass = `is-validable`;
		if (!form.checkValidity()) {
			return;
		}
		showPage = false;

		const res = await fetch(`/api/?payload=${JSON.stringify(formData)}`);
		if (res.ok) {
			const { user } = await res.json();
			if (user.allowFlag) {
				user.status = null;
				localStorage.setItem(`user`, JSON.stringify(user));
				userData.set(user);
			} else {
				localStorage.setItem(`user`, `{}`);
				userData.set({});
				formData.status = user.status;
				formData.password = ``;
				showPage = true;
			}
		}
	}
</script>

<svelte:head>
	<title>Вход</title>
</svelte:head>

{#if showPage}
	<h2 class="page__title">Вход в панель управления</h2>
	<form class="form {formData.validableClass}"
		bind:this={form} on:submit|preventDefault={submitHandler} novalidate>
		{#if formData.status}
			<p>{formData.status}</p>
		{/if}
		<div class="grid grid--gapped">
			<label class="field grid__col grid__col--4">
				<input class="field__input" bind:value={formData.login} required>
				<span class="field__label field__label--required">Логин</span>
			</label>
			<label class="field grid__col grid__col--4 grid__col--start-1">
				<input class="field__input" bind:value={formData.password} type="password" required>
				<span class="field__label field__label--required">Пароль</span>
			</label>
		</div>
		<button class="button form__submit" type="submit">Вход</button>
	</form>
{/if}
