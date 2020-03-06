<template>
  <NLink
    :to="`/books/${book.book_id}/cards`"
    :class="$style.wrap"
  >
    <div :class="$style.header">
      <h2 :class="$style.title">
        {{ book.book_title }}
      </h2>
      <p :class="$style.desc">
        {{ book.book_desc }}
      </p>
    </div>
    <div :class="[
      $style.footer,
      { [$style.mine]: user && user.user_id === book.user_id }
    ]">
      <!-- みんなの問題集 or お気に入り -->
      <template v-if="!user || (user && user.user_id !== book.user_id)">
        <cite :class="$style.author">
          Created by
          <span :class="$style.name">
            {{ book.book_username_created_by }}
          </span>
        </cite>
      </template>
      <!-- MY問題集 -->
      <template v-else-if="user && user.user_id === book.user_id">
        <div :class="$style.publish">
          <span v-if="book.book_is_publish">公開</span>
          <span v-if="!book.book_is_publish">非公開</span>
        </div>
      </template>
      <div
        @click.prevent="clickFavorite(book.book_id, book.book_is_favorite)"
        :class="[
          $style.favorite,
          { [$style.active]: book.book_is_favorite }
        ]"
      >
        {{ book.book_favorite_count }}いいね
      </div>
    </div>
  </NLink>
</template>

<script>
import { mapState } from 'vuex'
import Modal from '@/components/Modal'

export default {
  props: {
    book: {
      type: Object,
      required: true
    }
  },
  components: {
    Modal
  },
  data () {
    return {
      isOpenModal: false
    }
  },
  computed: {
    ...mapState('user', ['user'])
  },
  methods: {
    async clickFavorite (bookId, isFavorite) {
      if (!this.user) {
        this.$emit('requiredLogin')
        return false
      }
      isFavorite ?
        await this.$store.dispatch('book/deleteFavorite', { bookId }) :
        await this.$store.dispatch('book/saveFavorite', { bookId })
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: #00b8a9;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s;
}

.wrap:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.header {
  background-color: #00b8a9;
  padding: 15px;
  color: #fff;
}

.title {
  font-size: 16px;
  font-weight: bold;
  line-height: 1.3;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;

  @include mq(tb) {
    font-size: 15px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.desc {
  margin-top: 15px;
  font-size: 14px;
  line-height: 1.3;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;

  @include mq(tb) {
    font-size: 13px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.footer {
  font-size: 14px;
  background-color: #fff;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  @include mq(tb) {
    font-size: 13px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.footer.mine {
  padding: 7px 15px;
}

.author {
  font-style: normal;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.name {
  font-weight: bold;
}

.publish {
  $height-pc: 30px;
  $height-tb: 30px;
  $height-sp: 25px;

  width: 90px;
  height: $height-pc;
  border-radius: $height-pc / 2;
  background-color: #ffde7d;
  color: #999;
  font-size: 16px;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(tb) {
    font-size: 14px;
    width: 85px;
    height: $height-tb;
    border-radius: $height-tb / 2;
  }

  @include mq(sp) {
    font-size: 12px;
    width: 80px;
    height: $height-sp;
    border-radius: $height-sp / 2;
  }
}

.favorite {
  padding-left: 20px;
  margin-left: 10px;
  font-weight: bold;
  white-space: nowrap;
  color: #999;
  position: relative;
  transition: all 0.3s;
  cursor: pointer;

  @include mq(tb) {
    padding-left: 23px;
    font-size: 14px;
  }

  @include mq(sp) {
    padding-left: 18px;
    font-size: 12px;
  }
}

.favorite::before {
  $square-pc: 18px;
  $square-tb: 16px;
  $square-sp: 14px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
  background-image: url('~assets/images/heart-empty-gray.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center center;
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
  }
}

.favorite:hover {
  color: #f6416c;
  transition: all 0.3s;
}

.favorite:hover::before {
  background-image: url('~assets/images/heart-empty-red.svg');
}

.favorite.active {
  color: #f6416c;
}

.favorite.active::before {
  background-image: url('~assets/images/heart-active.svg');
}
</style>
