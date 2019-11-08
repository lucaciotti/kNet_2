<template>
    <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('{{ name }}') }"
    >
        <slot></slot>
        <transition name="fade">
            <span
            class="help-block"
            v-if="form.errors.has('{{ name }}')"
            v-text="form.errors.get('{{ name }}')"
            ></span>
        </transition>
    </div>
</template>

<script>
export default {
  props: ["name"]
};
</script>

//DA CAPIRE COME POTER INTERAGIRE