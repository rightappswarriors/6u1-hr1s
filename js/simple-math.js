function GetDateDiff(date1, date2)
{
	var start = new Date(date1);
	var end = new Date(date2);

	// end - start returns difference in milliseconds 
	var diff = new Date(end - start);

	// get days
	var days = diff/1000/60/60/24;

	return days+1;
}