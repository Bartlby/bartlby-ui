function bartlby_service_finish_hook(svc_obj, svc_table) 
-- called when a service check is finished, right before the _fin() - 
-- so you can overwrite state/output
-- You recieve a Table with tbe following keys
-- service_id, current_output, current_state -> use like svc_table["service_id"]
-- REMEMBER SCRIPT SIZE IS LIMITED TO 2048 characters - in worst case do a 
-- return dofile('/usr/local/scripts/1.lua')

-- you can call bartlby_service_set_status(svc_obj, state) - to set a new state
-- you can call bartlby_service_set_output(svc_obj, "new output") - to set new output text

	return 1
end

function bartlby_service_trigger_hook(svc_obj, svc_table) 
-- called right before a notification would be send
-- so you can cancel the notification - if you return a value <0
-- You recieve a Table with tbe following keys
-- service_id, current_output, current_state -> use like svc_table["service_id"]
-- return positiv to go on with trigger
-- return -1 - to skip this notification
-- return -2 - to force this message to  not aggregate
	return 1
end



-- in the main if you return a negative value no hook will be called
return 1