Ext.onReady(function(){

alert("xxxxxxxxxxxxx");
    // create the Data Store
    var store = new Ext.data.Store({
        // load using HTTP
        url: 'data_xml/konsep_surat.xml',

        // the return will be XML, so lets set up a reader
        reader: new Ext.data.XmlReader({
               // records will have an "Item" tag
               record: 'Item',
               id: 'ASIN',
               totalRecords: '@total'
           }, [
               // set up the fields mapping into the xml doc
               // The first needs mapping, the others are very basic
               {name: 'No', mapping: 'ItemAttributes > No'},
               'TanggalDibuat', 'Ditujukan', 'Perihal', 'None'
           ])
    });

    // create the grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {header: "No.", width: 50, dataIndex: 'No', sortable: false},
            {header: "Tanggal Dibuat", width: 130, dataIndex: 'TanggalDibuat', sortable: true},
            {header: "Ditujukan", width: 200, dataIndex: 'Ditujukan', sortable: true},
            {header: "Perihal", width: 330, dataIndex: 'Perihal', sortable: true},
            {header: "&nbsp;", width: 200, dataIndex: 'None', sortable: false}
        ],
        renderTo:'example-grid',
        width:935,
        height:200
    });

    store.load();
});
