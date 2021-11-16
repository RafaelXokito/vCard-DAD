<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" v-on:click="nextOrPrev(start)" aria-label="Previous">
                <span aria-hidden="true">&laquo;&laquo;</span>
                <span class="sr-only">Start</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" v-on:click="nextOrPrev(prev)" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <li v-for="(link, index) in links" v-bind:key="index" class="page-item" :class="link.active ? 'active': ''"><a style="cursor: pointer;" class="page-link" v-on:click="paginationChanged(link)">{{link.label}}</a></li>
        <li class="page-item">
            <a class="page-link" v-on:click="nextOrPrev(next)" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" v-on:click="nextOrPrev(end)" aria-label="Next">
                <span aria-hidden="true">&raquo;&raquo;</span>
                <span class="sr-only">End</span>
            </a>
        </li>
    </ul>
    </nav>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        data: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
        }
    },
    computed: {
        links(){
            console.log(this.data)
            return this.data.meta.links.filter((e, index) => {return index !== 0 && index !== this.data.meta.links.length-1})
        },
        next(){
            return this.data.links.next
        },
        prev(){
            return this.data.links.prev
        },
        start(){
            return this.data.links.first
        },
        end(){
            return this.data.links.last
        }
    },
    emits: [
        'pagination-change-page',
    ],
    methods: {
        paginationChanged(e){
            if (e.label !== '...') {
                this.$emit('pagination-change-page', e.url)
            }
        },
        nextOrPrev(e){
            this.$emit('pagination-change-page', e)
        },
    },
}
</script>

<style>

</style>