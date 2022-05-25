const i18n = require('./i18n');
require('./bootstrap');

import CreateMember from './components/members/CreateMember';

Vue.component('create-member', CreateMember);

let props = {};
const app = new Vue({
    i18n,
    el: "#create_member",
    render: (createElement) => { 
        return createElement(CreateMember, {props: props});
    },
}); 