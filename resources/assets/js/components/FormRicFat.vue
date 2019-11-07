<template>
  <form method="POST" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)" @change='checkGoOn'>
    <boxDefault header=true>
      <template v-slot:header>
        Dati Anagrafici
      </template>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('ragione_sociale') }"
      >
        <label>Codice Cliente</label>
        <input
          type="text"
          class="form-control"
          name="ragione_sociale"
          v-model="form.codicecf"
          disabled
        >
        <label>Ragione Sociale</label>
        <input
          type="text"
          class="form-control"
          name="ragione_sociale"
          v-model="form.ragione_sociale"
        >
        <label>Richiedente (Nome e Cognome)</label>
        <input
          type="text"
          class="form-control"
          name="richiedente"
          v-model="form.richiedente"
        >
        <label>Email</label>
        <input
          type="email"
          class="form-control"
          name="email_richiedente"
          v-model="form.email_richiedente"
        >
      </div>
    </boxDefault>
    
    <boxDefault header=true>
      <template v-slot:header>
        Richiesta Personalizzazione
      </template>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Tipologia Prodotto?</label>
        <v-multi-select
          v-model="form.sysKnown"
          :options="optionsSysOther"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('sysKnown')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysKnown')"
            v-text="form.errors.get('sysKnown')"
          ></span>
        </transition>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('final_note') }"
      >
        <label>Descrizione Personalizzazione</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.descr_pers"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('descr_pers')"
            v-text="form.errors.get('descr_pers')"
          ></span>
        </transition>
      </div>
    </boxDefault>

    <boxDefault header=true>
      <template v-slot:header>
        Prodotto di Riferimento
      </template>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Esiste un eventuale Prodotto di riferimento?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="true"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Prodotto Krona Koblenz
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="false"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Prodotto Concorrenza
        </pRadio>
      </div>
      
      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Selezionare System Krona Koblenz</label>
        <v-multi-select
          v-model="form.sysKnown"
          :options="optionsSysMkt"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('sysKnown')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysKnown')"
            v-text="form.errors.get('sysKnown')"
          ></span>
        </transition>
      </div>
      
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Inserire Breve Riferimento Prodotto</label>
          <input
            type="text"
            class="form-control"
            name="ragione_sociale"
            v-model="form.ragione_sociale"
          >
      </div>
    </boxDefault>

    <boxDefault header=true>
      <template v-slot:header>
        Info Tecniche e Commerciali
      </template>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('final_note') }"
      >
        <label>Inserire Note</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.descr_pers"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('descr_pers')"
            v-text="form.errors.get('descr_pers')"
          ></span>
        </transition>
      </div>
    </boxDefault>
    
    <boxDefault header=true>
      <template v-slot:header>
        Info di Produzione
      </template>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('final_note') }"
      >
        <label>Imballaggio Personalizzato?</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.descr_pers"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('descr_pers')"
            v-text="form.errors.get('descr_pers')"
          ></span>
        </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <!-- <v-select
          v-model="form.sysKnown"
          :options="optionsUM"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
        ></v-select> -->
        <label>Quantità</label>
          <input
            type="number"
            class="form-control"
            name="ragione_sociale"
            v-model="form.ragione_sociale"
          >
      </div>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Periodicità</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="true"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Mensile
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="false"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Trimestrale
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="false"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Annuale
        </pRadio>
      </div>
    </boxDefault>

    <boxDefault header=true>
      <template v-slot:header>
        Target Price
      </template>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Price</label>
          <input
            type="number"
            class="form-control"
            name="ragione_sociale"
            v-model="form.ragione_sociale"
          >
      </div>
    </boxDefault>

    <button type="submit" class="btn btn-success btn-block" :disabled="form.errors.any() || form.submitting" v-show="isTheEnd">
      <i v-if="form.submitting" class="fa fa-refresh fa-spin"></i>
      Salva
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
  props: ["contact", "sysmkt", "sysother", "ditta"],
  mixins: [redirect],

  data() {
    return {
      form: new Form({
        data_ricezione: '',
        richiedente: '',
        email_richiedente: '',
        ragione_sociale: JSON.parse(this.contact).descrizion,
        codicecf: JSON.parse(this.contact).codice,
        tipologia_prodotto: '',
        descr_pers: '',
        url_pers: '',
        system_kk: '',
        system_other: '',
        info_tecn_comm: '',
        imballaggio: '',
        um: '',
        quantity: '',
        periodo_ordinativi: '',
        target_price: '',
        ditta: this.ditta
      }),
      isConosceKK: null,
      oldConosceKK: null,
      isAcquistaKK: null,
      oldAcquistaKK: null,
      isTryKK: null,
      oldTryKK: null,
      isTheEnd: false,
      optionsUM: [
                    {
                        codice: 'PZ',
                        descrizion: 'Pezzi'
                    },
                    {
                        codice: 'CF',
                        descrizion: 'Confezioni'
                    }
                ],
      optionsSysMkt: JSON.parse(this.sysmkt),
      optionsSysOther: JSON.parse(this.sysother),
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
    onSubmit() {
      if(this.checkGoOn()){
        this.form.post("/storeModRicFatt")
        .then(response => {
          alert('Modulo Salvato! Verrai reindirizzato...');
          // window.location.replace('/contact/'+JSON.parse(this.contact).id);
        }).catch(error => {
          alert("C'è Stato un errore! Contattare Assistenza!");
        });
      }
    }
  }
};
</script>

<style>
@import "~/plugins/pretty-checkbox/css/pretty-checkbox.min.css";
@import "~/plugins/vue-multiselect/css/vue-multiselect.min.css";
</style>
