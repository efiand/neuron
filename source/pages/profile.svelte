<script>
	import { push } from 'svelte-spa-router';
	import { TOGGLE_DELAY, userData } from '../js/stores';

	let form;
	let showPage = true;
	let formData = null;
	userData.subscribe((value) => {
		formData = {
			...value,
			page: `/edit-user`,
			validableClass: ``,
			status: ``
		};
	});

	async function submitHandler() {
		formData.validableClass = `is-validable`;
		if (!form.checkValidity()) {
			return;
		}

		const res = await fetch(`/api/?payload=${JSON.stringify(formData)}`);
		if (res.ok) {
			const { user } = await res.json();
			console.log(user);
			localStorage.setItem(`user`, JSON.stringify(user));
			if (user.allowFlag) {
				userData.set(user);
				localStorage.setItem(`user`, JSON.stringify(user));
				formData.status = user.status;
			} else {
				showPage = false;
				localStorage.setItem(`user`, `{}`);
				userData.set({});
			}
		}
	}
</script>

<svelte:head>
	<title>Профиль</title>
</svelte:head>

{#if showPage}
	<h2 class="page__title grid__col--12">Настройки профиля</h2>

	<form class="form {formData.validableClass}"
		bind:this={form} on:submit|preventDefault={submitHandler} novalidate>
		{#if formData.status}
			<p>{formData.status}</p>
		{/if}
		<div class="grid grid--gapped">
			<label class="field grid__col grid__col--6">
				<input class="field__input" bind:value={formData.login} required>
				<span class="field__label field__label--required">Логин</span>
			</label>
			<label class="field grid__col grid__col--6">
				<input class="field__input" bind:value={formData.password} type="password">
				<span class="field__label">Новый пароль</span>
			</label>
			<label class="field grid__col grid__col--6">
				<input class="field__input" bind:value={formData.last_name} required>
				<span class="field__label field__label--required">Фамилия</span>
			</label>
			<label class="field grid__col grid__col--6">
				<input class="field__input" bind:value={formData.first_name} required>
				<span class="field__label field__label--required">Имя</span>
			</label>
			<label class="field grid__col grid__col--6">
				<input class="field__input" bind:value={formData.middle_name} required>
				<span class="field__label field__label--required">Отчество</span>
			</label>
		</div>
		<button class="button form__submit" type="submit">Сохранить</button>
	</form>
{/if}
