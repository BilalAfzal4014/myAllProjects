class PaginationHelper {

    static getPaginationInformation(chunkSize, pageNo, totalRows) {

        let startRecord = (chunkSize * pageNo) - chunkSize + 1;
        let endRecord = chunkSize * pageNo;
        endRecord = endRecord > totalRows ? totalRows : endRecord;
        let totalPages = Math.ceil(totalRows / chunkSize);

        return {
            //pagination_message: pageNo > totalPages ? `No record` : `showing ${startRecord} to ${endRecord} of ${totalRows} records`,
            page_no: pageNo,
            total_Pages: totalPages,
            start_record: startRecord,
            end_record: endRecord,
            total_rows: totalRows
        };

    }
}

module.exports = PaginationHelper;