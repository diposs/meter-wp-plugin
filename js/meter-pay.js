window.addEventListener('load', function() {
	const call =  document.querySelectorAll('.meter-payment-button');
	call.forEach(function (items){
      items.textContent = items.dataset.text;
	});
});
window.ethereum.request({
method: 'wallet_addEthereumChain',
params: [{
chainId: "0x53",
chainName: 'METER IO network',
nativeCurrency: {
    name: 'METER Coin',
    symbol: 'MTR',
    decimals: 18
},
rpcUrls: ['https://rpctest.meter.io/'],
blockExplorerUrls: ['https://scan-warringstakes.meter.io/']
}]
})
.catch((error) => {
console.log(error)
})
// Define Provider
