<template>
    <div>
        <button class="btn btn-success" @click="update">Update card details</button>
    </div>
</template>

<script>
import swal from 'sweetalert';
import axios from 'axios';

export default {
  props: ['email'],
  data() {
    return {
      handler: null,
    }
  },
  methods: {
    update: function() {

      this.handler.open({
        name: 'Bahdcasts',
        description: 'Bahdcasts Subscription',
        email: this.email,
        panelLabel: 'Update card details'
      })
    },

  },
  mounted() {
      this.handler = StripeCheckout.configure({
      key: 'pk_test_xdYdNZXqChbeOFKZSPWo27OX',
      image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
      locale: 'auto',
      allowRememberMe:false,
      token: function(token) {
        swal({ text: 'Please wait while we update your card details ...', buttons: false });
        axios.post('/card/update', {
          stripeToken: token.id
        }).then(res => {
          swal({ text: 'Successfully updated card details', icon: 'success' })
            .then(() => {
              window.location = '';
            });
        })
      }
    });
  },
}
</script>

