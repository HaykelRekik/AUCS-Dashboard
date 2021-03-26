<template>
    <section id="book_show">
        <div class="text-center">{{ book.author }} </div>
        <p class="text-center">{{ book.year }}</p>
    </section>
</template>

<script>

    import bookApi from "../api"

    export default {
        name: "BookView",
        components: {},
        data() {
            return {
                book: [],
            }
        },
        methods: {
            async fetchBook() {
                const response = await bookApi.find(this.$route.params.id)
                this.book = response.data.data
            },
        },
        mounted() {
            this.fetchBook()
        },
    }
</script>

<style lang="scss" scoped>

</style>
