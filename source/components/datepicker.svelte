<script>
	import { onMount } from 'svelte';
	import flatpickr from 'flatpickr';
	import { createEventDispatcher } from 'svelte';

	export let enableTime = false; // выбор даты со временем
	export let value = ``;
	let datePicker = null;

	const dispatch = createEventDispatcher();

	onMount(() => {
		datePicker.flatpickr({
			dateFormat: enableTime ? `Y-m-d H:i` : `Y-m-d`,
			defaultHour: 0,
			enableTime,
			minuteIncrement: 15,
			time_24hr: true
		});
	});
</script>

<input class="field__input" placeholder="Нажмите" bind:this={datePicker} bind:value={value}
	on:input={dispatch(`change`, { value })}>
