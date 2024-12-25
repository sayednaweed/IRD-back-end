<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE GetNgos(
                IN lang VARCHAR(10),               -- Language filter
                IN startDate DATE,                 -- Start date for filtering
                IN endDate DATE,                   -- End date for filtering
                IN searchColumn VARCHAR(50),       -- Column for search
                IN searchValue VARCHAR(255),       -- Value for search
                IN sortColumn VARCHAR(50),         -- Column for sorting
                IN sortOrder VARCHAR(4),           -- Order (ASC/DESC)
                IN perPage INT,                    -- Records per page
                IN page INT                        -- Current page number
            )
            BEGIN
                DECLARE offsetValue INT;           -- Offset for pagination

                -- Calculate the offset for pagination
                SET offsetValue = (page - 1) * perPage;

                -- Base query
                SET @query = 'SELECT 
                        ngos.id AS ngo_id, 
                        ngos.registration_no, 
                        ngo_trans.name AS ngo_name, 
                        ngos.establishment_date, 
                        ngo_statuses.operation AS status_ngo, 
                        agreements.expire_date
                    FROM ngos
                    JOIN ngo_trans ON ngos.id = ngo_trans.ngo_id
                    JOIN ngo_types ON ngos.ngo_type_id = ngo_types.id
                    LEFT JOIN ngo_statuses ON ngos.id = ngo_statuses.ngo_id
                    LEFT JOIN agreements ON ngos.id = agreements.ngo_id
                    WHERE ngo_trans.language_name = ?';

                -- Date filtering
                IF startDate IS NOT NULL THEN
                    SET @query = CONCAT(@query, ' AND ngos.establishment_date >= ?');
                END IF;

                IF endDate IS NOT NULL THEN
                    SET @query = CONCAT(@query, ' AND ngos.establishment_date <= ?');
                END IF;

                -- Search filtering
                IF searchColumn IS NOT NULL AND searchValue IS NOT NULL THEN
                    SET @query = CONCAT(@query, ' AND ', searchColumn, ' LIKE CONCAT(\'%\', ?, \'%\')');
                END IF;

                -- Sorting
                IF sortColumn IS NOT NULL AND sortOrder IS NOT NULL THEN
                    SET @query = CONCAT(@query, ' ORDER BY ', sortColumn, ' ', sortOrder);
                ELSE
                    SET @query = CONCAT(@query, ' ORDER BY ngos.registration_no ASC');
                END IF;

                -- Pagination
                SET @query = CONCAT(@query, ' LIMIT ', perPage, ' OFFSET ', offsetValue);

                -- Execute query
                PREPARE stmt FROM @query;
                EXECUTE stmt USING lang, startDate, endDate, searchValue;
                DEALLOCATE PREPARE stmt;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS GetNgos");
    }
};
