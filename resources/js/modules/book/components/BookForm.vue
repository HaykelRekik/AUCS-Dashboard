<template>
  <section id="book-form">
    <el-form
      :model="form"
      label-width="120px"
      ref="form"
      :rules="rules"
      size="small"
      @submit.native.prevent="saveSubmit"
    >
      <el-form-item
        label="Book Title"
        prop="title"
        :error="errors.get('title')"
      >
        <el-input
          v-model="form.title"
          @change="errors.clear('title')"
          suffix-icon="el-icon-edit"
        >
        </el-input>
      </el-form-item>
      <el-form-item
        label="Book Author"
        prop="author"
        :error="errors.get('autor')"
      >
        <el-input
          v-model="form.author"
          @change="errors.clear('author')"
          suffix-icon="el-icon-user"
        >
        </el-input>
      </el-form-item>
      <el-form-item
        label="Publishing Year"
        prop="year"
        :error="errors.get('year')"
      >
        <el-input
          v-model="form.year"
          type="number"
          @change="errors.clear('year')"
          suffix-icon="el-icon-date"
        >
        </el-input>
      </el-form-item>
            <el-form-item
        label="Stock"
        prop="stock"
        :error="errors.get('stock')"
      >
       <el-input-number v-model="form.stock" @change="errors.clear('stock')" :min="1"></el-input-number>        
      </el-form-item>


      
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click.native="cancel" size="small">{{
        $t("global.cancel")
      }}</el-button>
      <el-button
        type="success"
        @click.native="saveSubmit"
        :loading="formLoading"
        size="small"
        class="float-right"
        >{{ $t("global.save") }}</el-button
      >
    </div>
  </section>
</template>

<script>
import { Errors } from "../../../includes/Errors";
import bookApi from "../api";

export default {
  name: "BookForm",
  props: {
    initialForm: {
      default: () => ({}),
    },
  },
  data() {
    return {
      errors: new Errors(),
      formLoading: false,
      formTitle: "New Book",
      form: {},
      rules: {
        title: [{ required: true, message: "book title is required" }],
      },
    };
  },
  methods: {
    saveSubmit() {
      this.$refs["form"].validate((valid) => {
        if (valid) {
          this.formLoading = true;
          this.errors.clear();
          let action = this.form.id ? bookApi.update : bookApi.create;

          action(this.form)
            .then((response) => {
              this.$message({
                message: response.data.message,
                type: response.data.type,
              });
              if (response.data.type === "success") this.$emit("saved");
            })
            .catch((error) => {
              if (error.response.data.errors)
                this.errors.record(error.response.data.errors);
            })
            .finally(() => (this.formLoading = false));
        }
      });
    },
    cancel() {
      this.clearForm();
      this.$emit("cancel");
    },
    clearForm() {
      this.errors.clear();
      if (this.$refs["form"]) this.$refs["form"].resetFields();
    },
  },
  mounted() {
    this.form = Object.assign({}, this.initialForm);
  },
  watch: {
    initialForm: function (formData) {
      if (_.isEmpty(formData)) this.clearForm();
      this.form = Object.assign({}, formData);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
