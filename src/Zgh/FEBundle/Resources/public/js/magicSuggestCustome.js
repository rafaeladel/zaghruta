$('.ms-gmail').magicSuggest({
    sortOrder: 'countryName',
    selectionPosition: 'bottom',
    selectionStacked: false,
    displayField: 'countryName',
    value: [1,2],
    data: [{
        id: 0,
        countryName: 'France'
    }, {
        id: 1,
        countryName: 'United States'
    }, {
        id: 2,
        countryName: 'England'
    }, {
        id: 3,
        countryName: 'Germany'
    }, {
        id: 4,
        countryName: 'Japon'
    }, {
        id: 5,
        countryName: 'Spain'
    }, {
        id: 6,
        countryName: 'India'
    }, {
        id: 7,
        countryName: 'China'
    }]
});