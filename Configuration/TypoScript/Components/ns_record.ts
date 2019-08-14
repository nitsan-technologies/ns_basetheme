lib {
    // Get Record content
    getRecord = FLUIDTEMPLATE
    getRecord {

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
            as = NsRecord

            dataProcessing {
                10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                10 {
                        references.fieldName = image
                }
            }
        }
    }
}