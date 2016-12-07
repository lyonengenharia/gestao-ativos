<script>
    export default{
        props:['users'],
        data(){
            return {
                list:[],
                sortProperty: 'name',
                sortDirection:1
            }
        },
        created:function(){
            this.list = JSON.parse(this.users);
        }
    }
</script>
<template>
    <div>
        <pre></pre>
        <table class="table table-bordered table-striped">
            <thead>
            <th> <a href="#"> Nome</a></th>
            <th>E-mail</th>
            </thead>
            <tbody>
            <tr v-for="user in list ">
                <td>
                    {{ user.name }}
                </td>
                <td>
                    {{ user.email }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>