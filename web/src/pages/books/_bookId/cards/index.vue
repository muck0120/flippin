<template>
  <div :class="$style.wrap">
    <HeaderBook
      :is-footer="true"
      back-to="/books"
      @requiredLogin="isOpenModalLogin = true"
      @confirmDelete="isOpenModalDelete = true"
    />
    <div :class="$style.cards">
      <!-- 問題がある場合 -->
      <template v-if="cards.length > 0">
        <Draggable
          :value="cards"
          @input="changeCardsOrder"
          handle=".grabbable"
          :animation="200"
        >
          <div
            v-for="(card, index) in cards"
            :key="card.card_id"
            :class="$style.card"
          >
            <div
              v-if="user && book.user_id === user.user_id"
              :class="$style.sort"
              class="grabbable"
            >
              <fa :icon="faSort" />
            </div>
            <ListCard
              :to="`/books/${book.book_id}/cards/${card.card_id}`"
              :index="index"
              :card="card"
            />
          </div>
        </Draggable>
      </template>
      <!-- 問題がない場合 -->
      <template v-else>
        <NoCard />
      </template>
    </div>
    <div
      v-if="cards.length > 0"
      :class="$style.buttons"
    >
      <NLink
        :to="`/books/${bookId}/exam`"
        :class="[$style.button, $style.red]"
      >
        問題を解く
        <img
          src="@/assets/images/arrow-white.svg"
          :class="$style.icon"
        >
      </NLink>
      <NLink
        v-if="user && book.user_id === user.user_id"
        :to="`/books/${bookId}/cards/create`"
        :class="$style.button"
      >
        問題を追加する
        <fa
          :icon="faPlus"
          :class="$style.icon"
        />
      </NLink>
    </div>

    <!-- 問題集削除の確認モーダル -->
    <Modal
      v-if="isOpenModalDelete"
      @close="isOpenModalDelete = false"
    >
      <template #title>
        本当に削除しますか？
      </template>
      <template #desc>
        この問題集の中の問題も全て削除されます。<br>
        この操作は取り消せません。
      </template>
      <template #button>
        <ModalButtonTwo
          label-proceed="削除"
          label-cancel="キャンセル"
          @proceed="deleteBook()"
          @cancel="isOpenModalDelete = false"
        />
      </template>
    </Modal>

    <!-- ログイン要求のモーダル -->
    <Modal
      v-if="isOpenModalLogin"
      @close="isOpenModalLogin = false"
    >
      <template #title>
        ログインが必要です
      </template>
      <template #desc>
        お気に入りに登録するにはログインが必要です。<br>
        ログイン画面へ移動しますか？
      </template>
      <template #button>
        <ModalButtonTwo
          label-proceed="ログイン"
          label-cancel="キャンセル"
          @proceed="$router.push('/signin')"
          @cancel="isOpenModalLogin = false"
        />
      </template>
    </Modal>

    <!-- 通信エラー時のモーダル -->
    <Modal
      v-if="isOpenModalError"
      @close="isOpenModalError = false"
    >
      <template #title>
        通信エラー
      </template>
      <template #desc>
        エラーが発生しました。もう一度お試し下さい。
      </template>
      <template #button>
        <ModalButtonOne
          @proceed="isOpenModalError = false"
        />
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Draggable from 'vuedraggable'
import { faPlus, faSort } from '@fortawesome/free-solid-svg-icons'
import HeaderBook from '@/components/TheHeaderBook.vue'
import NoCard from '@/components/TheNoCard.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'
import ModalButtonTwo from '@/components/ModalButtonTwo'
import ListCard from '@/components/ListCard.vue'

export default {
  validate ({ params }) {
    return /^\d+$/.test(params.bookId)
  },
  async asyncData ({ store, params, error }) {
    const status = await store.dispatch(
      'book/fetchBook', { bookId: params.bookId }
    )
    if (status !== 200) error(status, 'error')
  },
  head () {
    return {
      title: this.book.book_title
    }
  },
  components: {
    HeaderBook,
    Draggable,
    NoCard,
    Modal,
    ModalButtonOne,
    ModalButtonTwo,
    ListCard
  },
  watchQuery: true,
  data () {
    return {
      isOpenModalLogin: false,
      isOpenModalDelete: false,
      isOpenModalError: false
    }
  },
  computed: {
    faPlus () {
      return faPlus
    },
    faSort () {
      return faSort
    },
    bookId () {
      return this.$route.params.bookId
    },
    ...mapState('user', ['user']),
    ...mapState('book', ['book']),
    ...mapState('card', ['cards'])
  },
  methods: {
    async deleteBook () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch(
        'book/deleteBook', { bookId: this.bookId }
      )
      if (status === 200) {
        this.$router.push('/books?tab=mines')
      } else {
        this.isOpenModalError = true
      }
      this.$nuxt.$loading.finish()
    },
    changeCardsOrder (cards) {
      const cardIds = cards.map(card => card.card_id)
      this.$store.dispatch('card/updateCardOrder', {
        bookId: this.book.book_id, payload: cardIds
      })
      this.$store.commit('card/setCards', cards)
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.cards {
  margin-top: 30px;
}

.card {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card + .card {
  margin-top: 10px;
}

.sort {
  font-size: 30px;
  padding-right: 10px;
  cursor: grab;

  @include mq(tb) {
    font-size: 27px;
    padding-right: 10px;
  }

  @include mq(sp) {
    font-size: 24px;
    padding-right: 5px;
  }
}

.sort:active {
  cursor: grabbing;
}

.buttons {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  bottom: 30px;
  left: 0;
  pointer-events: none;

  @include mq(tb) {
    bottom: 20px;
  }

  @include mq(sp) {
    bottom: 20px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 250px;
  height: $height-pc;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  pointer-events: auto;
  position: relative;

  @include mq(tb) {
    width: 200px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    width: 150px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 13px;
  }
}

.button.red {
  background-color: #f6416c;
  border: 1px solid #f6416c;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}

.icon {
  $square-pc: 20px;
  $square-tb: 16px;
  $square-sp: 12px;

  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 15px;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 10px;
  }
}

.button:hover .icon {
  transform: rotate(90deg);
  transition: all 0.3s;
}

.button.red:hover .icon {
  transform: translateX(5px);
}
</style>
