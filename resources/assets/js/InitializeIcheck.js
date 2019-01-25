export default {
  methods: {
    initialitzeICheck (field) {
      var component = this
      $('input[name=' + field + ']').on('ifChecked', function (event) {
        component.form.setField(field, true)
        component.form.errors.clear(field)
      }).on('ifUnchecked', function (event) {
        component.form.setField(field, '')
      })
    }
  }
}
