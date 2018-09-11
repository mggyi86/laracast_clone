<template>
    <div>
        <button class="btn btn-success" @click="subscribe('monthly')">Subscribe to $9.99 Monthly</button>
        <button class="btn btn-info" @click="subscribe('yearly')">Subscribe to $99.9 Yearly</button>
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
      plan: '',
      amount: 0,
    }
  },
  methods: {
    subscribe: function(plan) {
      if(plan == 'monthly') {
        window.stripePlan = 'monthly';
        this.amount = 999;
      } else {
        window.stripePlan = 'yearly';
        this.amount = 9999;
      }

      this.handler.open({
        name: 'Bahdcasts',
        description: 'Bahdcasts Subscription',
        amount: this.amount,
        email: this.email
      })
    },

  },
  mounted() {
      this.handler = StripeCheckout.configure({
      key: 'pk_test_xdYdNZXqChbeOFKZSPWo27OX',
      image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
      locale: 'auto',
      token: function(token) {
        swal({ text: 'Please wait while we subscribe you to a plan ...', buttons: false });
        axios.post('/subscribe', {
          stripeToken: token.id,
          plan: window.stripePlan
        }).then(res => {
          swal({ text: 'Successfully subscribed', icon: 'success' })
            .then(() => {
              window.location = '';
            });
        })
      }
    });
  },
}
</script>

