// Initiate TS to pass Data to Fluid
NsRecordList = FLUIDTEMPLATE
NsRecordList {

    // Set filename
    file = EXT:ns_basetheme/Resources/Private/Components/NsRecord.html

    // Data Processing
    dataProcessing.10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
    dataProcessing.10 {

        // Define your DB table
        table = tx_nsbasetheme_domain_model_record
        pidInList = {field:pid}
        pidInList.insertData = 1
        uidInList = {field:uid}
        uidInList.insertData = 1

        // Apply your special where claus
        // where = field=value

        // Pass as Object to Fluid
        as = NsRecordList

        // Pass Flexform data eg., Detail page insertData
        variables {
            detailPageId = TEXT
            detailPageId {
                value = {field:detailPageId}
                insertData = 1
            }
        }

        // Process the files and images
        dataProcessing {
            10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
            10 {
                references.fieldName = image
            }
        }
    }
}

// Let's just copy RecordLis and do the needful adaption
NsRecordDetail < NsRecordList
NsRecordDetail {
    dataProcessing.10 {

        // Set GET Params
        uidInList = {GP:uid}
        uidInList.insertData = 1

        // Pass with different Object
        as = NsRecordDetail
    }
}
