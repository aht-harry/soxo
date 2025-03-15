<template>
  <table class="table-result table-xsmb">
    <tbody>
      <tr>
        <th class="name-prize"></th>
        <td class="number-prize" id="mb_prizeCode">
          <span v-for="(code, index) in prizeCodes" :key="index" class="code-DB8" :id="'mb_prizeCode_item' + index">
            {{ code }}
          </span>
        </td>
      </tr>
      <tr v-for="(prize, index) in parsedPrizes" :key="index">
        <th>{{ prize.name }}</th>
        <td>
          <span v-for="(number, numIndex) in prize.numbers" :key="numIndex" :id="'mb_prize' + prize.id + '_item' + numIndex" :class="'prize' + prize.id">
            {{ number }}
          </span>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    prizeCodes: {
      type: Array,
      required: true,
      default: () => []
    },
    prizes: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  computed: {
    parsedPrizes() {
      return this.prizes.map((prize, index) => {
        return {
          name: index === 0 ? 'ĐB' : index.toString(),
          id: index + 1,
          numbers: prize.split(',')
        };
      });
    }
  }
}
</script>

<style scoped>
.table-result {
  width: 100%;
  border-collapse: collapse;
}
.table-result th, .table-result td {
  border: 1px solid #ddd;
  padding: 8px;
}
.table-result th {
  background-color: #f2f2f2;
  text-align: left;
}
</style> 