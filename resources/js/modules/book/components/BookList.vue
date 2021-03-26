<template>
    <section id="book">
        <!-- filters -->
        <el-col :span="24" class="m-t-10">
            <el-form :inline="true" :model="filters" @submit.native.prevent="fetchData" size="mini">
                <el-form-item>
                    <el-button type="primary" @click="handleAdd" icon="el-icon-plus">{{$t('global.add')}}</el-button>
                </el-form-item>

                <el-form-item class="mr-0 float-right">
                    <el-input v-model="filters.search" @input="applySearch" :placeholder="$t('global.search')">
                        <i slot="suffix" class="el-input__icon el-icon-error" v-if="filters.search.length" @click="clearSearch"></i>
                    </el-input>
                </el-form-item>

            </el-form>
        </el-col>

        <!-- table -->
        <el-table
            :data="books"
            highlight-current-row
            v-loading="booksLoading"
            @sort-change="handleSortChange"
            @filter-change="handleFilterChange"
            class="w-100">
            <el-table-column prop="id" label="Id" width="80"></el-table-column>
            <el-table-column prop="title" label="title" min-width="200" sortable>
                <template slot-scope="scope">
                    <router-link class="el-link el-link--default ellipsis-form" :to="{name: 'Show Book', params: {id: scope.row.id}}">
                        <span class="el-link--inner item_name">{{ scope.row.title }}</span>
                    </router-link>
                </template>
            </el-table-column>
            <el-table-column prop="author" label="author" width="200"></el-table-column>
            <el-table-column prop="stock" label="Qte. in stock" width="150" sortable></el-table-column>
            <el-table-column prop="year" label="year" width="100" sortable></el-table-column>

            <el-table-column prop="updated_at" label="Updated" width="130" sortable :sort-orders="sortOrders">
                <template slot-scope="updated_at">
                    {{ GlobalFormatDate(updated_at.row.updated_at) }}
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="130" align="right">
                <template slot-scope="scope">
                    <el-tooltip
                        :open-delay="300"
                        placement="top"
                        :content="$t('global.edit')">
                        <span>
                            <el-button
                                @click="handleEdit(scope.row)"
                                size="mini"
                                icon="el-icon-edit">
                            </el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip
                        :open-delay="300"
                        placement="top"
                        :content="$t('global.delete')">
                        <span>
                            <el-button
                                @click="handleDelete(scope.row)"
                                type="danger"
                                size="mini"
                                icon="el-icon-delete">
                            </el-button>
                        </span>
                    </el-tooltip>
                </template>
            </el-table-column>
        </el-table>

        <!-- pagination -->
        <el-pagination
            layout="sizes, prev, pager, next"
            :current-page.sync="page"
            :page-size.sync="globalPageSize"
            :total="booksMeta.total"
            class="float-right mt-3 mb-3">
        </el-pagination>

        <!-- form dialog -->
        <el-dialog
            :title="formTitle"
            :visible.sync="formVisible"
            close-on-click-modal
            class="books-dialog">
            <BookForm :initialForm="formData" @saved="saved" @cancel="formVisible = false" />
        </el-dialog>
    </section>
</template>

<script>

    import {mapActions, mapGetters, mapMutations} from 'vuex'
    import {BOOK_FETCH, BOOK_OBTAIN} from "../store/types";
    import bookApi from '../api'
    import BookForm from "./BookForm";

    export default {
        name: "BookList",
        components: {BookForm},
        data: () => ({
            sortBy: 'id,asc',
            sortOrders: ['ascending', 'descending'],
            filters: {
                search: ''
            },
            page: 1,
            formVisible: false,
            formTitle: 'New Book',
            formData: null
        }),
        computed: {
            ...mapGetters(['books', 'booksMeta', 'booksLoading']),
        },
        created() {
            this.fetchData()
        },
        watch:{
            page: function () {
                this.fetchData();
            },
            pageSize: function () {
                this.fetchData();
            },
        },
        methods: {
            ...mapActions([BOOK_FETCH]),
            ...mapMutations([BOOK_OBTAIN]),
            handleSortChange(val) {
                if (val.prop != null && val.order != null) {
                    let sort = val.order.startsWith('a') ? 'asc' : 'desc';
                    this.sortBy = val.prop + ',' + sort;
                    this.fetchData();
                }
            },
            handleFilterChange() {
                this.fetchData();
            },
            fetchData() {
                let params = {
                    page: this.page,
                    search: this.filters.search,
                    sortBy: this.sortBy,
                    pageSize: this.globalPageSize
                };
                this[BOOK_FETCH](params)
            },
            handleAdd() {
                this.formTitle = 'New Book';
                this.formData = {};
                this.formVisible = true;
            },
            handleEdit(row) {
                this.formTitle = 'Edit Book';
                this.formData = Object.assign({}, row);
                this.formVisible = true;
            },
            handleDelete(row) {
                this.$confirm('This will permanently delete the book. Continue?', 'Warning', {
                    type: 'warning'
                }).then(() => {
                    bookApi.delete(row.id).then((response) => {
                        this.$message({
                            message: response.data.message,
                            type: response.data.type
                        })
                        this.fetchData()
                    })
                })
            },
            applySearch: _.debounce( function() {
                this.fetchData();
            }, 300),
            clearSearch() {
                this.filters.search = '';
                this.fetchData();
            },
            saved() {
                this.formVisible = false
                this.fetchData()
            }
        }
    }
</script>
<style lang="scss" scoped>

</style>
