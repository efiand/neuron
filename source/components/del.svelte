<script>
	import Icon from 'svelte-icon';
	import DelIcon from '../sprite/del.svg';
	import { createEventDispatcher } from 'svelte';

	export let dbtable; // имя таблицы из БД
	export let colname = `id`; // имя столбца, по которому ищется запись
	export let colvalue; // значение ячейки, по которому ищется запись
	export let stringFlag = false; // значение не строковое

	const dispatch = createEventDispatcher();
	let disabled = false;

	async function deleteHandler() {
		dispatch(`start`);

		disabled = true;
		const res = await fetch(`/api/?payload=${JSON.stringify({
			page: `/delete`,
			dbtable,
			colname,
			colvalue,
			stringFlag
		})}`);

		dispatch(`del`);
	}
</script>

<button class="iconable" type="button" on:click={deleteHandler} {disabled}>
	<span class="sr-only">Удалить запись</span>
	<Icon data={DelIcon}/>
</button>
