<script>
	import Preloader from '../components/preloader.svelte';
	import { link } from 'svelte-spa-router';
	import Del from '../components/del.svelte';

	let newPatient = {};
	let patients = [];
	let asyncPatients = getPatients();

	async function getPatients() {
		const res = await fetch(`/api/?payload=${JSON.stringify({
			patient: `/get-patients`
		})}`);
		if (res.ok) {
			const data = await res.json();
			patients = data.patients;
		}
	}

	async function addPatient() {
		const res = await fetch(`/api/?payload=${JSON.stringify({
			patient: `/add-patient`, newPatient
		})}`);
		if (res.ok) {
			refresh();
		}
	}

	function refresh() {
		asyncPatients = getPatients();
	}
</script>

<svelte:head>
	<title>Пациенты</title>
</svelte:head>

{#await asyncPatients}
	<Preloader/>
{:then res}
	<div>
		{#each patients as patient (patient.id)}
			<div class="item {patient.pale ? `item--pale` : ``}">
				<a class="item__title" href="/patients/{patient.id}" use:link>
					{patient.last_name} {patient.first_name} {patient.middle_name}
				</a>
				<div class="item__del">
					<Del dbtable="patients" colvalue={patient.id} on:del={refresh} on:start={() => patient.pale = true}/>
				</div>
			</div>
		{/each}
	</div>
{/await}
