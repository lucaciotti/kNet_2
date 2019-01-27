import Form from "acacha-forms";
// import initialitzeIcheck from './InitializeIcheck'
import redirect from "./redirect";
import PrettyInput from "pretty-checkbox-vue/input";
import PrettyCheck from "pretty-checkbox-vue/check";
import PrettyRadio from "pretty-checkbox-vue/radio";
import vSelect from "vue-select";

Vue.component("p-input", PrettyInput);
Vue.component("p-check", PrettyCheck);
Vue.component("p-radio", PrettyRadio);
Vue.component("v-select", vSelect);

const app = new Vue({
  el: "#app",

  mixins: [redirect],

  data: {
    selected: null,
    check: null,
    form: new Form({
      ragioneSociale: "",
      typeProdPorte: "",
      checked: "",
      radio: ""
      // typeProd_finestre: "",
      // typeProd_mobili: ""
    })
  },

  components: {},

  methods: {
    onSubmit() {
      this.form.post("/storeModCarp01").then(response => {
        // window.location.reload();
      });
    }
  },

  /* mounted: function () {
        jQuery("input").on("ifChecked", function (e) {
            app.$data.checked = true;
        });
        jQuery("input").on("ifUnchecked", function (e) {
            app.$data.checked = false;
        });
    } */

  mounted() {
    // this.initialitzeICheck('checked')
  }
});
