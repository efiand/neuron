<script>
	import { userData } from '../js/stores';

	let showPage = true;
	let user = {};
	userData.subscribe((value) => {
		user = value;
	});

	async function logout() {
		showPage = false;

		const res = await fetch(`/api/?payload=${JSON.stringify({
			page: `/logout`
		})}`);
		if (res.ok) {
			localStorage.setItem(`user`, `{}`);
			userData.set({});
		}
	}
</script>

<svelte:head>
	<title>Neuron</title>
</svelte:head>

{#if showPage}
	<h2 class="page__title">Рабочее место невролога</h2>
	<form class="form" on:submit|preventDefault={logout}>
		<p>
			<b>Роль</b>: {user.role_name}.<br>
			<b>ФИО</b>: {user.last_name} {user.first_name} {user.middle_name}.
		</p>
		{#if user.task_history}
			<p>Проект в разработке. Дополнение технического задания <a href={user.task_history} target="_blank">здесь</a>.</p>
		{/if}
		<button class="button form__submit">Выход</button>
	</form>
{/if}
