const i18n = require("./i18n");
require("./bootstrap");

import CompanySelect from "./components/CompanySelect";

let props = {};
const app = new Vue({
    i18n,
    el: "#company_select",
    render: (createElement) => {
        return createElement(CompanySelect, { props: props });
    },
});
