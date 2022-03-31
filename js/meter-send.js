async function sendfunds() {
  const provider = new ethers.providers.Web3Provider(window.ethereum, "any");
  await provider.send("eth_requestAccounts", []);
  const signer = provider.getSigner();
  let userAddress = await signer.getAddress();
	var address = "";
	var amount = "";
	var calls =  document.querySelectorAll('.meter-payment-button');
	calls.forEach(function (itemd){
	  address = itemd.dataset.addressid;
	itemd.addEventListener('click', event => {
       amount = parseFloat(event.target.dataset.amounts);
	if (amount < 0.001) {
        amount = 0.001;
      }
	  localStorage.setItem("amounttobespent", amount);
	});
	 amount = localStorage.getItem("amounttobespent");
	});
	const tx = await signer.sendTransaction({
		from: userAddress,
		to: address,
		value: ethers.utils.parseEther(amount),
		gasLimit: 90000
	  });
};