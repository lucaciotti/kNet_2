<template>
  <div class="box box-default">
    <div class="box-header with-border" v-show="header">
      <h3 class="box-title" data-widget="collapse">
        <slot name="header"></slot>
      </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body">
      <slot></slot>
    </div>
    <div class="box-footer" v-show="footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ["header", "footer"]
};
</script>
