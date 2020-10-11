import { writable } from 'svelte/store';

export const DESKTOP_WIDTH = 1260;
export const TOGGLE_DELAY = 600;

export const userData = writable(JSON.parse(localStorage.getItem(`user`)) || {});
