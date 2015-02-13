function bartlby_trigger_hook(svc_obj, svc_table) 
-- You recieve a Table with tbe following keys
-- service_id, current_output, current_state -> use like svc_table["service_id"]
-- REMEMBER SCRIPT SIZE IS LIMITED TO 2048 characters - in worst case do a 
-- return dofile('/usr/local/scripts/1.lua')

	return 1
end

-- in the main if you return a negative value no hook will be called
return 1

