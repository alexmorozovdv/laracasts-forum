<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-4">
            <textarea name="body"
                      id="body"
                      class="form-control"
                      rows="5"
                      placeholder="Have something to say?"
                      required
                      v-model="body"></textarea>
            </div>

            <button
                type="submit"
                class="btn btn-primary"
                @click="addReply">Post
            </button>
        </div>

        <p class="text-center mt-4" v-else>
            Please <a href="/login">sign in</a> to participate in this
            discussion.
        </p>
    </div>
</template>

<script>

import Tribute from "tributejs"

export default {
    data() {
        return {
            body: '',
        };
    },

    mounted() {
        let tribute = new Tribute({
            // column to search against in the object (accepts function or string)
            lookup: 'value',
            // column that contains the content to insert by default
            fillAttr: 'value',
            values: function(query, cb) {
                axios.get('/api/users', {params: {name: query}} )
                    .then(function(response){
                        console.log(response);
                        cb(response.data);
                    });
            },
        });
        tribute.attach(document.querySelectorAll("#body"));
    },


    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    },

    methods: {
        addReply() {
            axios.post(location.pathname + '/replies', {body: this.body})
                .catch(error => {
                    flash(error.response.data, 'danger');
                })
                .then(({data}) => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
                });
        }
    }
}

</script>
