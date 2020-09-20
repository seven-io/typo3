define([], () => {
    const $feUsers = document.getElementById('feUsers');
    const $to = document.getElementById('to');
    const options = [...$feUsers.options];
    const feUsers = options.map(o => o.value);
    const getTo = () => $to.value.split(',');

    const to = getTo();
    options
        .filter(o => to.includes(o.value))
        .forEach(o => o.selected = true);

    const onChange = () => {
        const selectedFeUsers = [...$feUsers.selectedOptions].map(s => s.value);

        let to = getTo();
        to.filter(u => feUsers.includes(u) && !selectedFeUsers.includes(u))
            .forEach(u => to.splice(to.findIndex(_u => u === _u), 1));
        to.push(...selectedFeUsers.filter(u => !to.includes(u)));
        to = to.join(',');

        $to.value = to.startsWith(',') ? to.substring(1) : to;
    };

    $feUsers.addEventListener('change', onChange);
});