function updateDirectionLabels() 
{
	const tl = document.getElementById("tl");
	const tr = document.getElementById("tr");
	const bl = document.getElementById("bl");
	const br = document.getElementById("br");

	const tb = document.getElementById("tblabel"); 
	const lr = document.getElementById("lrlabel");

	if (tl.checked) 
	{
		tb.textContent = "Distance from top";
		lr.textContent = "Distance from left";
	}
	
	if (tr.checked) 
	{
		tb.textContent = "Distance from top";
		lr.textContent = "Distance from right";
	}
	
	if (bl.checked) 
	{
		tb.textContent = "Distance from bottom";
		lr.textContent = "Distance from left";
	}
	
	if (br.checked) 
	{
		tb.textContent = "Distance from bottom";
		lr.textContent = "Distance from right";
	}
}


document.getElementById("tl").addEventListener("change", updateDirectionLabels);
document.getElementById("tr").addEventListener("change", updateDirectionLabels);
document.getElementById("bl").addEventListener("change", updateDirectionLabels);
document.getElementById("br").addEventListener("change", updateDirectionLabels);
updateDirectionLabels();