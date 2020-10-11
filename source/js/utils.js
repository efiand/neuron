export const copyToClipboard = (str) => {
	try {
		navigator.clipboard.writeText(str);
	} catch (err) {
		alert(`Ваш браузер не поддерживает копирование по клику`);
	}
};
