<template>
  <form method="POST" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
    <boxDefault>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('ragioneSociale') }"
      >
        <label>Ragione Sociale</label>
        <input
          type="text"
          class="form-control"
          name="ragioneSociale"
          v-model="form.ragioneSociale"
          disabled
        >
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('typeProd') }">
        <label>Tipologia di Produzione</label>
        <div>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProdPorte"
            v-model="form.typeProdPorte"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Porte
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProdFineste"
            v-model="form.typeProdFinestre"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Finestre
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProdMobili"
            v-model="form.typeProdMobili"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Mobili
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProdCucine"
            v-model="form.typeProdCucine"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Cucine
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProdOther"
            v-model="form.typeProdOther"
            @change="clearNoteProd"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Altro
          </pCheck>
        </div>
        <span
          class="help is-danger"
          v-if="form.errors.has('descrizione')"
          v-text="form.errors.get('descrizione')"
        ></span>
      </div>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('noteProdOther') }"
        v-show="form.typeProdOther"
      >
        <label>Descrivi Altra Tipologia di Produzione...</label>
        <textarea
          class="form-control"
          rows="5"
          v-model="form.noteProdOther"
          placeholder="Inserisci Note"
        ></textarea>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Conosce Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rConosceKK"
          color="info"
          v-model="form.rConosceKK"
          value="true"
          @change="boolConosceKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rConosceKK"
          color="info"
          v-model="form.rConosceKK"
          value="false"
          @change="boolConosceKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI CONOSCE KK  -->
    <boxDefault v-show="isConosceKK && isConosceKK!=null">
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Quali Prodotti di Krona Koblenz Conosce?</label>
        <v-multi-select
          v-model="form.sysKnown"
          :options="optionsSysMkt"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="listSysKnown"
        ></v-multi-select>
        <span
          class="help is-danger"
          v-if="form.errors.has('sysKnown')"
          v-text="form.errors.get('sysKnown')"
        ></span>
      </div>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Acquisto i Prodotti Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rAcquistaKK"
          color="info"
          v-model="form.rAcquistaKK"
          value="true"
          @change="boolAcquistaKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rAcquistaKK"
          color="info"
          v-model="form.rAcquistaKK"
          value="false"
          @change="boolAcquistaKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI CONOSCE KK E ACQUISTA KK  -->
    <boxDefault v-show="(isAcquistaKK && isAcquistaKK!=null)">
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysBuyOfKK') }">
        <label>Quali Prodotti di Krona Koblenz Acquista?</label>
        <v-multi-select
          v-model="form.sysBuyOfKK"
          :options="preSysKnown"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
        ></v-multi-select>
        <span
          class="help is-danger"
          v-if="form.errors.has('sysBuyOfKK')"
          v-text="form.errors.get('sysBuyOfKK')"
        ></span>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('yes_supplierType') }"
      >
        <label>Da chi acquista?</label>
        <div>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="yes_supplierType"
            color="info"
            v-model="form.yes_supplierType"
            value="1"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Ferramenta
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="yes_supplierType"
            color="info"
            v-model="form.yes_supplierType"
            value="2"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Gruppo Commerciale
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="yes_supplierType"
            color="info"
            v-model="form.yes_supplierType"
            value="3"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Direttamente dal Produttore
          </pRadio>
        </div>
        <span
          class="help is-danger"
          v-if="form.errors.has('yes_supplierType')"
          v-text="form.errors.get('yes_supplierType')"
        ></span>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('yes_supplierName') }"
        v-show="form.yes_supplierType>1"
      >
        <label>Ragione Sociale del Fornitore</label>
        <input type="text" class="form-control" name="nameSupplier" v-model="form.yes_supplierName">
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Viene regolarmente informato sui Nuovi Prodotti di Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="yes_isInformato"
          color="info"
          v-model="form.yes_isInformato"
          value="true"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="yes_isInformato"
          color="info"
          v-model="form.yes_isInformato"
          value="false"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI NON CONOSCE KK  -->
    <boxDefault
      v-show="(!isConosceKK && isConosceKK!=null) || (!isAcquistaKK && isAcquistaKK!=null)"
    >
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('sysBuyOfOther') }"
      >
        <label>Quali Sistemi Utilizza?</label>
        <v-multi-select
          v-model="form.sysBuyOfOther"
          :options="optionsSysMkt"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
        ></v-multi-select>
        <span
          class="help is-danger"
          v-if="form.errors.has('sysBuyOfOther')"
          v-text="form.errors.get('sysBuyOfOther')"
        ></span>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('typeProd') }">
        <label>Cosa le ha fatto scegliere questi prodotti?</label>
        <div>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_noinfo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Non Conosco Krona Koblenz
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_catalogo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Catalogo
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_prezzo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Prezzo
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_qualita"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Qualità
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_servizio"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Servizio
          </pCheck>
        </div>
        <span
          class="help is-danger"
          v-if="form.errors.has('descrizione')"
          v-text="form.errors.get('descrizione')"
        ></span>
      </div>

      <hr>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('not_supplierType') }"
      >
        <label>Da chi acquista?</label>
        <div>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="1"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Ferramenta
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="2"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Gruppo Commerciale
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="3"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Direttamente dal Produttore
          </pRadio>
        </div>
        <span
          class="help is-danger"
          v-if="form.errors.has('not_supplierType')"
          v-text="form.errors.get('not_supplierType')"
        ></span>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('not_supplierType') }"
        v-show="form.not_supplierType>1"
      >
        <label>Ragione Sociale del Fornitore</label>
        <input type="text" class="form-control" name="nameSupplier" v-model="form.not_supplierName">
      </div>
    </boxDefault>

    <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">
      <i v-if="form.submitting" class="fa fa-refresh fa-spin"></i>
      {{ trans('_message.submit') }}
    </button>
  </form>
</template>

<script>
import Form from "acacha-forms";
import redirect from "../redirect";
import pInput from "pretty-checkbox-vue/input";
import pCheck from "pretty-checkbox-vue/check";
import pRadio from "pretty-checkbox-vue/radio";
import vSelect from "vue-select";
import vMultiSelect from "vue-multiselect";
import boxDefault from "./layouts/BoxDefault";

export default {
  props: ["contact", "sysmkt"],
  mixins: [redirect],

  data() {
    return {
      form: new Form({
        ragioneSociale: JSON.parse(this.contact).descrizion,
        typeProdPorte: false,
        typeProdFinestre: false,
        typeProdCucine: false,
        typeProdMobili: false,
        typeProdOther: false,
        noteProdOther: "",
        rConosceKK: null,
        rAcquistaKK: null,
        sysKnown: "",
        sysBuyOfKK: "",
        sysBuyOfOther: "",
        sysLiked: "",
        yes_supplierType: "",
        yes_supplierName: "",
        not_why_prezzo: false,
        not_why_qualita: false,
        not_why_servizio: false,
        not_why_catalogo: false,
        not_why_noinfo: false,
        not_supplierType: "",
        not_supplierName: ""
      }),
      isConosceKK: null,
      isAcquistaKK: null,
      optionsSysMkt: JSON.parse(this.sysmkt),
      preSysKnown: [{ codice: "", descrizione: "none" }]
    };
  },

  compute: {},

  components: {
    pCheck,
    pRadio,
    vSelect,
    vMultiSelect,
    boxDefault
  },

  methods: {
    clearNoteProd() {
      if (!this.form.typeProdOther) {
        this.form.noteProdOther = "";
      }
    },

    boolConosceKK() {
      this.isConosceKK = this.form.rConosceKK === "true" ? true : false;
    },

    boolAcquistaKK() {
      this.isAcquistaKK = this.form.rAcquistaKK === "true" ? true : false;
    },

    listSysKnown() {
      this.preSysKnown = this.form.sysKnown;
    },

    onSubmit() {
      this.form.post("/storeModCarp01").then(response => {
        // window.location.reload();
      });
    }
  }
};
</script>

<style>
@import "~/plugins/pretty-checkbox/css/pretty-checkbox.min.css";
@import "~/plugins/vue-multiselect/css/vue-multiselect.min.css";
</style>
