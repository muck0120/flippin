<template>
  <div :class="$style.wrap">
    <FormBook
      :title.sync="form.title"
      :desc.sync="form.desc"
      :isPublish.sync="form.isPublish"
      @submit="storeBook()"
      @cancel="$router.push('/books')"
    />
    <Modal
      v-if="isOpenModal"
      @close="isOpenModal = false"
    >
      <template #title>
        通信エラー
      </template>
      <template #desc>
        エラーが発生しました。もう一度お試し下さい。
      </template>
      <template #button>
        <ModalButtonOne @proceed="isOpenModal = false" />
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import FormBook from '@/components/TheFormBook.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'

export default {
  head () {
    return {
      title: '新規問題集の作成'
    }
  },
  middleware: ['authenticated'],
  components: {
    FormBook,
    Modal,
    ModalButtonOne
  },
  data () {
    return {
      form: {
        title: '',
        desc: '',
        isPublish: true
      },
      isOpenModal: false
    }
  },
  computed: {
    ...mapState('book', ['book'])
  },
  methods: {
    async storeBook () {
      this.$nuxt.$loading.start()
      const status =
        await this.$store.dispatch('book/storeBook', this.form)
      if (status === 200) {
        this.$router.push(`/books/${this.book.book_id}/cards`)
      } else {
        this.isOpenModal = true
      }
      this.$nuxt.$loading.finish()
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}
</style>
