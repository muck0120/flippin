<template>
  <div :class="$style.wrap">
    <FormBook
      :title.sync="form.title"
      :desc.sync="form.desc"
      :isPublish.sync="form.isPublish"
      @submit="updateBook()"
      @cancel="$router.push(`/books/${$route.params.bookId}/cards`)"
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
  validate ({ params }) {
    return /^\d+$/.test(params.bookId)
  },
  async asyncData ({ store, params, error }) {
    const status = await store.dispatch(
      'book/fetchBook', { bookId: params.bookId }
    )

    if (status !== 200) {
      error(status, 'error')
      return false
    }

    const user = store.state.user.user
    const book = store.state.book.book
    if (user.user_id !== book.user_id) {
      error(401, 'Unauthorized')
      return false
    }

    return {
      form: {
        bookId: book.book_id,
        title: book.book_title,
        desc: book.book_desc || '',
        isPublish: book.book_is_publish
      }
    }
  },
  head () {
    return {
      title: '問題集の更新'
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
      isOpenModal: false
    }
  },
  computed: {
    ...mapState('book', ['book'])
  },
  methods: {
    async updateBook () {
      this.$nuxt.$loading.start()
      const status =
        await this.$store.dispatch('book/updateBook', this.form)
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
