export const finish = (first, last) => {
    window.console.log(`The user name is '${first}' and the id is '${last}'`);
    if (window.location !== window.parent.location) {
        window.parent.postMessage({
            'msg': 'stop',
        }, "*");
    }
};
