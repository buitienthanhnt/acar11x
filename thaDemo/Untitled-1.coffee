PaypalServerSdkLib\Models\Order {#651 // app/Http/Controllers/PayPalTestController.php:45
  -createTime: "2025-12-19T09:57:56Z"
  -updateTime: null
  -id: "8P749142U2556624V"
  -paymentSource: 
PaypalServerSdkLib\Models
\
PaymentSourceResponse
 {#649
    -card: null
    -paypal: 
PaypalServerSdkLib\Models
\
PaypalWalletResponse
 {#693
      -emailAddress: null
      -accountId: null
      -accountStatus: null
      -name: null
      -phoneType: null
      -phoneNumber: null
      -birthDate: null
      -businessName: null
      -taxInfo: null
      -address: null
      -attributes: null
      -storedCredential: null
      -experienceStatus: null
    }
    -bancontact: null
    -blik: null
    -eps: null
    -giropay: null
    -ideal: null
    -mybank: null
    -p24: null
    -sofort: null
    -trustly: null
    -applePay: null
    -googlePay: null
    -venmo: null
  }
  -intent: "CAPTURE"
  -payer: null
  -purchaseUnits: array:1 [
    0 => 
PaypalServerSdkLib\Models
\
PurchaseUnit
 {#676
      -referenceId: "default"
      -amount: 
PaypalServerSdkLib\Models
\
AmountWithBreakdown
 {#695
        -currencyCode: "USD"
        -value: "23.00"
        -breakdown: 
PaypalServerSdkLib\Models
\
AmountBreakdown
 {#688
          -itemTotal: 
PaypalServerSdkLib\Models
\
Money
 {#680
            -currencyCode: "USD"
            -value: "23.00"
          }
          -shipping: null
          -handling: null
          -taxTotal: null
          -insurance: null
          -shippingDiscount: null
          -discount: null
        }
      }
      -payee: 
PaypalServerSdkLib\Models
\
PayeeBase
 {#707
        -emailAddress: "sb-yqfw036798124@business.example.com"
        -merchantId: "HWEZZF8JPXDBG"
      }
      -paymentInstruction: null
      -description: null
      -customId: null
      -invoiceId: null
      -id: null
      -softDescriptor: null
      -items: array:1 [
        0 => 
PaypalServerSdkLib\Models
\
Item
 {#716
          -name: "test product"
          -unitAmount: 
PaypalServerSdkLib\Models
\
Money
 {#663
            -currencyCode: "USD"
            -value: "23.00"
          }
          -tax: null
          -quantity: "1"
          -description: null
          -sku: null
          -url: null
          -category: null
          -imageUrl: "https://www.amuaglobal.icu/storage/files/global/zelensky-17-3066-1686410843.jpg"
          -upc: null
          -billingPlan: null
        }
      ]
      -shipping: null
      -supplementaryData: 
PaypalServerSdkLib\Models
\
SupplementaryData
 {#717
        -card: null
        -risk: null
      }
      -payments: null
      -mostRecentErrors: null
    }
  ]
  -status: "PAYER_ACTION_REQUIRED"
  -links: array:2 [
    0 => 
PaypalServerSdkLib\Models
\
LinkDescription
 {#701
      -href: "https://api.sandbox.paypal.com/v2/checkout/orders/8P749142U2556624V"
      -rel: "self"
      -method: "GET"
    }
    1 => 
PaypalServerSdkLib\Models
\
LinkDescription
 {#710
      -href: "https://www.sandbox.paypal.com/checkoutnow?token=8P749142U2556624V"
      -rel: "payer-action"
      -method: "GET"
    }
  ]
}