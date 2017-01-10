/**
 * Created by wfs on 05/12/2016.
 */
Vue.component('select2', {
    props: ['options', 'value'],
    template: '#select2-template',
    mounted: function () {
        var vm = this
        $(this.$el)
            .val(this.value)
            // init select2
            .select2({data: this.options})
            // emit event on change.
            .on('change', function () {
                vm.$emit('input', this.value)
            })
    }
});