const i18n = require('./i18n');
require('./bootstrap');

import EditMember from './components/members/EditMember';

Vue.component('edit-member', EditMember);

let props = {};
const app = new Vue({ 
    i18n,
    el: "#edit_member",
    render: (createElement) => { 
        return createElement(EditMember, {props: props});
    },
}); 